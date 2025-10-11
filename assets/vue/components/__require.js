import app from "../instance"
import SeatsList from "./Seat/SeatsList.vue";
import Navbar from "./Navbar.vue";
import EventListing from "./Event/EventListing.vue";
import Reservation from "./Reservation/Reservation.vue";
import EventsListAdmin from "./Event/admin/EventsListAdmin.vue";
import RoomsListAdmin from "./Room/admin/RoomsListAdmin.vue";

app.component("seats-list",SeatsList);
app.component("navbar",Navbar);
app.component("event-list",EventListing);
app.component("form-reservation",Reservation);


app.component('events-list-admin', EventsListAdmin);
app.component('rooms-list-admin', RoomsListAdmin);
