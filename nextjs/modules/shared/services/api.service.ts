import axios from "axios";

export class ApiService {
  async head(url: string): Promise<Response> {
    return await axios(url, {
      method: "HEAD",
      mode: "no-cors",
      cache: "no-cache",
      credentials: "same-origin",
      headers: {
        "Content-Type": "application/json",
      },
      redirect: "follow",
      referrerPolicy: "no-referrer",
    });
  }

  async get<T>(url: string): Promise<T> {
    const response = await axios(url, {
      method: "GET",
      cache: "no-cache",
      credentials: "same-origin",
      headers: {
        "Content-Type": "application/json",
        "Access-Control-Allow-Origin": "*",
        "X-Requested-With": "XMLHttpRequest",
      },
      redirect: "follow",
      referrerPolicy: "no-referrer",
    });

    return response.data;
  }

  async post<T>(url: string, data: BodyInit): Promise<T> {
    const response = await axios(url, {
      method: "POST",
      headers: {
        "Access-Control-Allow-Origin": "*",
        "X-Requested-With": "XMLHttpRequest",
      },
      data: data,
    });

    return response.data;
  }
}
