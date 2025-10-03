import app from "../instance"
import SeatsList from "./Seat/SeatsList.vue";
import Navbar from "./Navbar.vue";
import EventListing from "./Event/EventListing.vue";

app.component("seats-list",SeatsList);
app.component("navbar",Navbar);
app.component("event-list",EventListing);
