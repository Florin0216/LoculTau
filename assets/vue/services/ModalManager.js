import {createApp, h, nextTick, ref} from "vue";
// import i18n from "@/i18n_instance";


class ModalManager {
    open = (options = {}) => {
        const container = document.createElement('div');
        document.body.appendChild(container);

        const instanceRef = ref();

        options.props = options.props ? options.props : {};

        options.props.instance = instanceRef;

        const modalPromise = new Promise((resolve) => {
            nextTick(() => {
                const modalElement = container.querySelector('#exampleModal');
                if (modalElement) {
                    const bootstrapModal = new bootstrap.Modal(modalElement);

                    // calculate z-index
                    const openModals = document.querySelectorAll('.modal.show').length;
                    const zIndex = 1050 + openModals * 10;

                    modalElement.style.zIndex = zIndex;

                    bootstrapModal.show();

                    const backdrop = document.querySelector('.modal-backdrop:last-child');
                    if (backdrop) backdrop.style.zIndex = zIndex - 1;

                    instanceRef.value = {
                        modal: bootstrapModal,
                        close: (data) => {
                            bootstrapModal.hide();
                            resolve(data);
                        },
                    };

                    modalElement.addEventListener('hidden.bs.modal', () => {
                        document.body.removeChild(container);
                        app.unmount();
                    });
                }
            });
        })

        const app = createApp({
            render: () => h(options.component, options.props)
        });

        app
            // .use(i18n)
            .mount(container);

        return modalPromise;
    }

}

export default new ModalManager();
