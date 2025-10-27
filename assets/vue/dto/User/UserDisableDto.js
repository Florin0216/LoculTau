
class UserDisableDto {
    isDisabled = null;

    constructor(user) {
        this.isDisabled = user.isDisabled;
    }
}

export default UserDisableDto;
