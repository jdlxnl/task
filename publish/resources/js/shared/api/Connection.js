import axios from 'axios';
axios.defaults.withCredentials = true;

/**
 * The initialization options
 * @typedef {Object} connection~options
 * @property {string=} baseUrl - The base url to the API
 */

export default class Connection {
  /**
   * @param {connection~options} options
   */
  constructor(options) {
    this.client = axios.create(options);
  }

  /**
   * Call API using GET
   *
   * @param {string} route - The route to call
   * @param {object=} params - The call arguments
   * @returns {Promise<*>}
   */
  async GET(route, params = null) {
    return this.call(route, 'GET', false, params);
  }

  /**
   * Call API using POST
   *
   * @param {string} route - The route to call
   * @param {object} params - The call arguments
   * @returns {Promise<*>}
   */
  async POST(route, data = null) {
    return this.call(route, 'POST', data);
  }

  /**
   * Call API using PUT
   *
   * @param {string} route - The route to call
   * @param {object=} data - The data to be submitted.
   * @returns {Promise<*>}
   */
  async PUT(route, data = null) {
    return this.call(route, 'PUT', data);
  }

  /**
   * Call API using DELETE
   *
   * @param {string} route - The route to call
   * @param {object=} data - The data to be submitted.
   * @returns {Promise<*>}
   */
  async DELETE(route, data = null) {
    return this.call(route, 'DELETE', data);
  }

  /**
   *
   * @param {string} route - The route to call
   * @param {string} method - HTTP method
   * @param {object=} data - The data to be submitted.
   * @param {object=} params - The call arguments
   * @returns {Promise<*>}
   */
  async call(route, method = "GET", data = null, params = null) {

    const request = {
      url: route,
      method,
      params,
      data,
      headers: {
        Accept: 'application/json'
      },
    };

    try {
      const response = await this.client.request(request);
      return response.data;
    } catch (error) {

      // Allow client to deal with regular errors
      // Or intercept
      if (error.response) {
        return error.response.data;
      } else {
        throw (error);
      }
    }
  }

  /**
   * Shortcut to: axios.interceptors.request.use
   * https://www.npmjs.com/package/axios#interceptors
   *
   * @param {requestCallback} onSuccess
   * @param {requestCallback} onError
   */
  beforeRequest(onSuccess, onError) {
    this.client.interceptors.request.use(onSuccess, onError);
  }

  /**
   * Shortcut to: axios.interceptors.response.use
   * https://www.npmjs.com/package/axios#interceptors
   *
   * @param {requestCallback} onSuccess
   * @param {requestCallback} onError
   */
  afterResponse(onSuccess, onError) {
    this.client.interceptors.response.use(onSuccess, onError);
  }

  /**
   * Shortcut to: axios.interceptors.request.eject
   * https://www.npmjs.com/package/axios#interceptors
   *
   * @param {requestCallback} listener
   */
  stopBeforeRequest(listener) {
    this.client.interceptors.request.eject(listener);
  }

  /**
   * Shortcut to: axios.interceptors.response.eject
   * https://www.npmjs.com/package/axios#interceptors
   *
   * @param {requestCallback} listener
   */
  stopAfterResponse(listener) {
    this.client.interceptors.request.eject(listener);
  }

}
