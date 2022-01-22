import AuthProvider from "./AuthProvider";

export default class SpaAuthProvider extends AuthProvider {

  /**
   *
   * @param {Api} api
   */
  constructor(api) {
    super();
    this.api = api;
    this.connection = api.connection;
    this.profile = null;
  }

  async init() {
    this.connection.afterResponse(null, this.checkIfSessionExpired.bind(this));

    const result = await this.api.profile.me();

    if (result.data) {
      this.profile = result.data;
      return this.profile;
    }

    return false;
  }

  async login(credentials){
    const result = await this.api.auth.login(credentials);

    if(result.data){
      this.profile = result.data;
    }

    return result;
  }


  async logout() {
    const result = await this.api.auth.logout();
    if(result.data){
      this.profile =  null;
      // Trigger logged out
    }
  }

  async checkAuth() {
    return this.profile !== null;
  }

  async getProfile() {
    return this.profile;
  }


  /**
   *
   * @param error
   */
  checkIfSessionExpired(error) {
    if (error.response?.status === 401 && this.profile) { // Unauthorized && Not Logged In
        this.profile = null;
        // Trigger logged out - Expired
    }
    return Promise.reject(error);
  }

}
