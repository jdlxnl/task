import React from 'react';
import TaskLogDetails from "../components/TaskLogDetails";
import api from "../../services/api";
import {withRouter} from "react-router";

class TaskLogList extends React.Component {
    constructor(props) {
        super(props);
        this.state = {loading: true};
    }

    componentDidMount() {
        this.loadData();
    }

    async loadData() {
        const {match} = this.props;
        const id = match.params.id;
        const response = await api.task.get(id);

        this.setState({
            data: response.data,
            loading: false
        });
    }

    render() {
        const {loading, data} = this.state;
        return (
            <div className="card">
                <div className="card-header d-flex align-items-center justify-content-between">
                    <h5>Task Log</h5>
                </div>
                <div
                    className="d-flex flex-column align-items-center justify-content-center card-bg-secondary bottom-radius">
                    {loading && <div
                        className="d-flex align-items-center justify-content-center card-bg-secondary p-5 bottom-radius">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                             className="icon spin mr-2 fill-text-color">
                            <path
                                d="M12 10a2 2 0 0 1-3.41 1.41A2 2 0 0 1 10 8V0a9.97 9.97 0 0 1 10 10h-8zm7.9 1.41A10 10 0 1 1 8.59.1v2.03a8 8 0 1 0 9.29 9.29h2.02zm-4.07 0a6 6 0 1 1-7.25-7.25v2.1a3.99 3.99 0 0 0-1.4 6.57 4 4 0 0 0 6.56-1.42h2.1z"></path>
                        </svg>
                        <span>Loading...</span></div>}
                    {!loading && <TaskLogDetails data={data}/>}
                </div>
            </div>
        );
    }
}

export default withRouter(TaskLogList);
