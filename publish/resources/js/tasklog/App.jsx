import React from 'react';
import { withRouter } from "react-router";
import Routing from './Routing';

class App extends React.Component {

    constructor() {
        super();
        this.state = {
            location: null,
            intervalId: null
        };
    }

    componentDidMount() {
        const intervalId = setInterval(() => {
            if (this.state.location !== document.location.href) {
                this.props.history.push(document.location);
                this.setState({location: document.location.href});
            }
        }, 50);

        this.setState({intervalId});
    }

    componentWillUnmount() {
        clearInterval(this.state.intervalId);
    }

    render() {
        return (<Routing/>);
    }
}

export default withRouter(App);
