export default class Password extends HTMLDivElement {

    constructor() {
        super()
        this.showPassword = false
        this.classList.add('input-group')

        this.onClick = this.onClick.bind(this)
    }

    connectedCallback() {
        const e = document.createElement('div')
        e.className = 'input-group-append'
        e.innerHTML = `<div class="input-group-text">${this.icon()}</div>`

        this.appendChild(e)
        this.querySelector('.input-group-text').addEventListener('click', this.onClick)
    }

    onClick(e) {
        e.preventDefault()
        this.showPassword = !this.showPassword
        this.querySelector('.input-group-text').innerHTML = this.icon()
        this.querySelector('input').type = this.showPassword ? 'text' : 'password'
    }

    icon() {
        return this.showPassword
            ? '<i class="mdi mdi-eye-off">'
            : '<i class="mdi mdi-eye"></i>'
    }
}
