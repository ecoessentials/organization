import { Controller } from 'stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    static values = {
        prototype: String,
    }

    static targets = [
        'list'
    ]

    connect() {
        this.counter = this.listTarget.children.length;
    }

    add() {
        const newWidget = this.prototypeValue.replace(/__name__/g, this.counter);
        const newElement = document.createElement('template');
        newElement.innerHTML = newWidget;
        this.listTarget.append(newElement.content);
        this.counter++;
    }

    remove(event) {
        let elt = event.currentTarget;
        while (elt.parentNode !== this.listTarget) {
            elt = elt.parentNode;
        }
        elt.remove();
    }
}
