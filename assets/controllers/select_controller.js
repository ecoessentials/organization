import { Controller } from 'stimulus';
import SlimSelect from 'slim-select';

export default class extends Controller {
    static values = {
        options: Object
    };

    connect() {
        this.slimselect = new SlimSelect({
            select: this.element.nodeName === 'SELECT' ? this.element : this.element.querySelector('select'),
            ...this.optionsValue,
            // addToBody: true,
            searchPlaceholder: 'Rechercher...'
        });
    }

    disconnect() {
        this.slimselect.destroy();
    }
}