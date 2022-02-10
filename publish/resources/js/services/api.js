import {API_SERVER} from "./config";
import Api from "../shared/api/";

export default new Api({baseURL: `${API_SERVER}/api/v1`});
