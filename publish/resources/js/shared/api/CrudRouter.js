import Router from "./Router";


/**
 * The initialization options
 * @typedef {Object} crud-router~options
 * @property {string} entity - The name of the entity
 */


/**
 * The initialization options
 * @typedef {Object} crud~list-request
 * @property {int} page - The page index
 * @property {int} limit - The number of items to return
 * @property {string} sort - The sort order
 * @property {object} filter - Filters to apply
 */


export default class CrudRouter extends Router{

  /**
   *
   * @param {crud-router~options} props
   */
  constructor(props) {
    super(props);
    this.base = "/" + props.entity.toLowerCase();
  }


  /**
   * @param {crud~list-request} parameters
   * @returns {Promise<*>}
   */
  async list(parameters){
    return await this.connection.GET(`${this.base}`, parameters);
  };

  async create(data){
    return await this.connection.POST(`${this.base}`, data);
  };

  async get(id){
    return await this.connection.GET(`${this.base}/${id}`);
  };

  async update(id, data){
    return await this.connection.PUT(`${this.base}/${id}`, data);
  };

  async delete(id){
    return await this.connection.DELETE(`${this.base}/${id}`);
  };

  }

