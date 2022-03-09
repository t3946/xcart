export class ApiService {
  async head(url: string): Promise<Response> {
    return await fetch(url, {
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
    const response = await fetch(url, {
      method: "GET",
      mode: "cors",
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

    return response.json();
  }

  async post<T>(url: string, data: BodyInit): Promise<T> {
    const response = await fetch(url, {
      method: "POST",
      mode: "cors",
      cache: "no-cache",
      credentials: "same-origin",
      redirect: "follow",
      referrerPolicy: "no-referrer",
      headers: {
        "Access-Control-Allow-Origin": "*",
        "X-Requested-With": "XMLHttpRequest",
      },
      body: data,
    });

    return response.json();
  }
}
