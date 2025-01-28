import { createToast } from "mosha-vue-toastify";
import "mosha-vue-toastify/dist/style.css";

export const errorMessage = function (title, description, position) {
    createToast(
        { title: title, description: description },
        {
            toastBackgroundColor: '#AF1E1E',
            type: "danger",
            showIcon: true,
            hideProgressBar: true,
            position: position,
            showCloseButton: false,
            transition: "bounce",
            timeout: 3000,

        }
    );
};
export const successMessage = function (title, description, position) {
    createToast(
        {title: title, description: description},
        {
            toastBackgroundColor: '#0FA958',
            showIcon: true,
            showCloseButton: false,
            hideProgressBar: true,
            position: position,
            type: "success",
            transition: "bounce",
            timeout: 3000,
        }
    );
};
export const warningMessage = function (title, description, position) {
    createToast(
        {title: title, description: description},
        {
            // toastBackgroundColor: '#ffc43d',
            showIcon: true,
            showCloseButton: false,
            hideProgressBar: true,
            position: position,
            type: "warning",
            transition: "bounce",
            timeout: 3000,
        }
    );
};

export const notificationMessage = function (title, description, position) {
    createToast(
        {title: title, description: description},
        {
            toastBackgroundColor: '#2465C7',
            showIcon: true,
            showCloseButton: false,
            hideProgressBar: true,
            position: position,
            type: "success",
            transition: "bounce",
            timeout: 3000,
        }
    );
};
