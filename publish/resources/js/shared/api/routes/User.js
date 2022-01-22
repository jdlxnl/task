import CrudRouter from "../CrudRouter";

export default class User extends CrudRouter {
  constructor(props) {
    props.entity = "user";
    super(props);
  }
}

