import { Horizon as HorizonIcon } from "./icon/horizon";
import QueuePlayNextIcon from "@mui/icons-material/QueuePlayNext";
import Module from "../../lib/module-manager/Module";
import TaskLogApi from "./TaskApi";
import TaskApi from "./TaskApi";
import ListIcon from '@mui/icons-material/List';

class ApiModule extends Module {
  setupNavigation(navigationManager) {
    navigationManager.injectHeader("Development");
    navigationManager.injectPageAt(["Development"], {
      title: "Task Management",
      children: [
        {
          title: "Horizon",
          path: "/horizon",
          icon: <HorizonIcon fontSize="small" />,
        },
        {
          title: "Tasklog",
          path: "/dashboard/tasks",
          icon: <ListIcon fontSize="small" />,
        },
      ],
      icon: <QueuePlayNextIcon fontSize="small" />,
    });
  }

  registerApiRoutes(apiManager) {
    apiManager.api.task = new TaskApi(apiManager.getConnection());
    apiManager.api.taskLog = new TaskLogApi(apiManager.getConnection());
  }
}

export default ApiModule;
