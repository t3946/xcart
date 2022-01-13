const nodemailer = require("nodemailer");

const transporter = nodemailer.createTransport({
  host: "smtp.gmail.com",
  port: 465,
  secure: true, // upgrade later with STARTTLS
  auth: {
    user: "vl0809081@gmail.com",
    pass: "lqwocjfsndiplmiv",
  },
});

module.exports = transporter;
