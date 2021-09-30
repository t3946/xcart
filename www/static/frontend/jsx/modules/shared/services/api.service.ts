export class ApiService {
  async get<T>(url: string): Promise<T> {
    const response = await fetch(url, {
      method: "GET",
      mode: "cors",
      cache: "no-cache",
      credentials: "same-origin",
      headers: {
        "Content-Type": "application/json",
      },
      redirect: "follow",
      referrerPolicy: "no-referrer",
    });

    return response.json();
  }

  async post<T>(
    url: string,
    data: BodyInit,
    headers: Record<any, any> = {
      "Content-Type": "application/json",
    }
  ): Promise<T> {
    const response = await fetch(url, {
      method: "POST",
      mode: "cors",
      cache: "no-cache",
      credentials: "same-origin",
      headers,
      redirect: "follow",
      referrerPolicy: "no-referrer",
      body: data,
    });

    return response.json();
  }
}
