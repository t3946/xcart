export const editEmailAddress = (email: string): string => {
  return email.match(/[^<]+@[^.]+.[^>]+/)[0];
};
