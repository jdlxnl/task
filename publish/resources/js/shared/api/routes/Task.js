import CrudRouter from "../CrudRouter";

export default class User extends CrudRouter {
  constructor(props) {
    props.entity = "task-log";
    super(props);
  }

  /**
   * @param {crud~list-request} parameters
   * @returns {Promise<*>}
   */
  async restart(id) {
    return await this.connection.POST(`${this.base}/${id}/restart`);
  };
}

