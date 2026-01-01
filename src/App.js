import React from 'react';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';
import PetList from './PetList';
import AddPet from './AddPet';

function App() {
    return (
        <Router>
            <Switch>
                <Route path="/add-pet" component={AddPet} />
                <Route path="/" exact component={PetList} />
            </Switch>
        </Router>
    );
}

export default App;
