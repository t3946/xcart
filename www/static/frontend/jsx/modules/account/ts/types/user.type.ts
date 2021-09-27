export interface User {
  user_id: string;
  avatar_image: string | null;
  cart_number: string | null;
  email: string;
  experience_comment: string | null;
  location: string | null;
  name: string;
  password: string;
  phone: string | null;
  phone_country_code: string | null;
  public_name: string | null;
  rate_us: string | null;
  tsv_count: string | null;
  tsv_secret: string;
}
