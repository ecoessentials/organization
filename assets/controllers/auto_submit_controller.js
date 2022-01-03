import { Controller } from 'stimulus';

export default class extends Controller {
    static targets = ['action'];

    connect() {
        this.element.addEventListener('turbo:submit-end', () => {
            this.setAction('');
        });
    }

    submit(event) {
        let action = event.currentTarget.dataset.autoSubmitAction;
        if (action === undefined) {
            action = event.currentTarget.name;
        }
        this.setAction(action);
        this.element.requestSubmit();
    }

    setAction(action) {
        if (this.hasActionTarget) {
            this.actionTarget.value = action;
        }
    }
}