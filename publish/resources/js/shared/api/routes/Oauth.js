import Router from "../Router";

export default class Oauth extends Router{
  async redirect(provider, data) {
    // Set tokens
    return await this.connection.GET(`/oauth/${provider}/redirect`, data);
  }

  async get(url) {
      return await this.connection.GET(url);
  }


  async create(provider, data) {
    // Set tokens
    return await this.connection.POST(`/oauth/${provider}/create`, data);
  }
}

