import Router from "../Router";

export default class Auth extends Router{
    async login(credentials) {
        // Set tokens
        await this.csfr();
        return await this.connection.POST('/auth/login', credentials);
    }

  async logout(credentials) {
    // Set tokens
    return await this.connection.POST('/auth/logout');
  }


    async csfr() {
        return await this.connection.GET('/auth/csfr');
    }
}

