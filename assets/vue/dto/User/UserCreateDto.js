
class UserCreateDto {
    username = null;
    email = null;
    firstName = null;
    lastName = null;
    roles = [];
    password = null;

    constructor(user) {
        this.username = user.username;
        this.email = user.email;
        this.firstName = user.firstName;
        this.lastName = user.lastName;
        this.roles = user.roles;
        this.password = user.password;
    }
}

export default UserCreateDto;
