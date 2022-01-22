import Connection from './Connection';
import Auth from './routes/Auth';
import Profile from './routes/Profile';
import User from './routes/User';
import Task from "./routes/Task";
import Oauth from "./routes/Oauth";

export default class Api {
  /**
   * @param {connection~options} options
   */
  constructor(options) {
    this.connection = new Connection(options);
    this.auth = new Auth(this.connection);
    this.profile = new Profile(this.connection);
    this.user = new User(this.connection);
    this.task = new Task(this.connection);
    this.oauth = new Oauth(this.connection);
  }
}
