export const replaceCidToImage = (body: string, attachments: any[]) => {
  attachments.forEach((e) => {
    if (e.cid) {
      body = body.replace(
        `cid:${e.cid}`,
        ` https://i1.s3stores.com/${e.attachment}`
      );
    }
  });

  return body;
};
