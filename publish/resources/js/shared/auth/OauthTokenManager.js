import {
  CLIENT_ID,
  CLIENT_SECRET
}                         from "../../services/config";
const debug = require('debug')('core:tokenmanager');

/**
 * Does all the token management for the connection.
 *
 * All token updates should happen through here.
 *
 * DO NOT use AsyncStorage. The share modal and app do not
 * share the same storage, and you get a mismatch between
 *
 */
export class OauthTokenManager {
  constructor(api) {
    this.api = api;
  }

  async bootstrap() {
    let access = null;
    try {
      access = await this.getAccess();
    }catch(e){
      console.error(e);
    }
    debug(`Access key ${access?'found':'not found'}`);
    return true;
  }

  async refresh() {
    const refreshToken =  await this.getRefreshToken();

    if(!refreshToken){
      debug("Can't refresh no refresh token");
      return false;
    }

    // ADD A LOCK HERE SO NOT ALL CALLS GOO CRAZY
    const body = {
      refresh_token: refreshToken,
      grant_type: 'refresh_token',
      client_id: CLIENT_ID,
      client_secret: CLIENT_SECRET
    };

    debug("Updating tokens", body);
    try {
      const response = await this.api.POST('/oauth/token', body, true);
      if(response.error){
        debug("Unable to update tokens", response);
        await this.removeAccess();
        return false
      }else{
        debug("Updating tokens");
        await this.updateAccess(response);
        return true;
      }
    }catch (e){
      debug("Unable to update tokens");
      await this.removeAccess();
      return false;
    }
  }

  /**
   * Async so we add refresh logic later
   */
  async getAccessToken() {
    const access = await this.getAccess();
    return access?.access_token ?? false;
  }

  async getRefreshToken() {
    const access = await this.getAccess();
    return access?.refresh_token ?? false;
  }

  async getAccess() {
    let tokens = localStorage.getItem('tokens');

    return tokens ? JSON.parse(tokens) : false;
  }

  async updateAccess(tokens) {
    const current = await this.getAccess() || {};
    // Merge tokens
    this.access = {...current, ...tokens};
    const json = JSON.stringify(this.access);
    await localStorage.setItem("tokens", json);
    //await AsyncStorage.setItem("tokens", json);
  }

  async removeAccess() {
    debug("tokens cleared");
    await localStorage.removeItem("tokens");
    //await AsyncStorage.removeItem("tokens");
  }

  hasToken(){
      let tokens = localStorage.getItem('tokens');
      return tokens ? true : false;
  }
}
