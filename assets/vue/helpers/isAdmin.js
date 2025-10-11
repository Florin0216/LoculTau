import {isValue} from "./isValue";

export function isAdmin(user) {
    if (!isValue(user)) {
        return false;
    }

    return user.roles.includes('ROLE_ADMIN');
}
