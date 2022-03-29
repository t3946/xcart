const isAuthMiddleware = require("../middleware/isAuth");
const mail = require("../services/mail");
const app = require("express")();
const PrismaClient = require("@prisma/client").PrismaClient;
const prisma = new PrismaClient();

app.post("/report-abuse", isAuthMiddleware, async function (req, res) {
  const user = await prisma.xcart_users.findUnique({
    where: {
      user_id: req.user.userId,
    },
  });

  const review = await prisma.xcart_product_reviews.findUnique({
    where: {
      product_review_id: req.body.reviewId,
    },
  });

  const data = {
    from: process.env.EMAIL_FROM,
    to: "helpdesk@s3stores.com",
    subject: "Review abuse from " + user.name,
    text: `Abuse on review with id(${review.product_review_id}) from user with id(${user.user_id})`,
    html: `<p>Abuse on review with id(${review.product_review_id}) from user with id(${user.user_id})</p>`,
  };

  mail.sendMail(data, function () {
    res.sendStatus(200);
  });
});

app.post(
  "/get-current-user-comment",
  isAuthMiddleware,
  async function (req, res) {
    const review = await prisma.xcart_product_reviews.findFirst({
      where: {
        user_id: req.user.userId,
        product_id: req.body.reviewId,
      },
    });

    res.json({ review });
  }
);

module.exports = app;
