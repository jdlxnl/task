import CrudRouter from "../../lib/api/CrudRouter";

export default class TaskApi extends CrudRouter {
  constructor(props) {
    props.entity = "task-log";
    super(props);
  }
}
