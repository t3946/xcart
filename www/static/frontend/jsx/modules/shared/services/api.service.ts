export class ApiService {
  async get<T>(url = ""): Promise<T> {
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

  async post<T>(url = "", data: BodyInit): Promise<T> {
    console.log(data);
    const response = await fetch(url, {
      method: "POST",
      body: data,
    });

    try {
      return response.json();
    } catch (e) {
      return null;
    }
  }
}
