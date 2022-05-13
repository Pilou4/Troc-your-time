class Carousel 
{
    /**
     * @param {HTMLElement} element
     * @param {Object} options
     * @param {Object} [options.slidesToScroll=1] Nombre d'éléments à faire défiler
     * @param {Object} [options.slidesVisible=1] Nombre d'élémentsvisible dans un slide
     * @param {Booleen} [options.loop=false] Doit-t-on bouvler en fin de carousel
     * @param {Booleen} [options.pagination=false]
     * @param {Booleen} [options.infinite=false]
     * @param {Booleen} [options.navigation=true]
     * @param {Booleen} [options.turnInfinite=false]
     */
    constructor (element, options = {}) {
        this.element = element
        this.options = Object.assign({}, {
            slidesToScroll: 1,
            slidesVisible: 1,
            loop: false,
            pagination: false,
            navigation: true,
            infinite: false,
            turnInfinite: false
        }, options)
        if (this.options.loop && this.options.infinite) {
            throw new ('Un carousel ne peut être à la fois en boucle et en infini')
        }
        let children = [].slice.call(element.children)
        this.isMobile = false        
        this.currentItem = 0
        this.moveCallback = []
        this.offset = 0

        // Modifications du DOM
        this.root = this.createDivWithClass('carousel')
        this.container = this.createDivWithClass('carousel__container')
        this.root.setAttribute('tabindex', '0')
        this.root.appendChild(this.container)
        this.element.appendChild(this.root)
        this.items = children.map((child) => {
            let item = this.createDivWithClass('carousel__item')
            item.appendChild(child)
            return item
        });
        if (this.options.infinite) {
            this.offset = this.slidesVisible + this.options.slidesToScroll
            if (this.offset > children.length) {
                console.error("Vous n(avez pas assez d'élément dans le carousel", element)
            }
            this.items = [
                ...this.items.slice(this.items.length - this.offset).map(item => item.cloneNode(true)),
                ...this.items,
                ...this.items.slice(0, this.offset).map(item => item.cloneNode(true)),
            ]
            this.goToItem(this.offset, false)
        }
        this.items.forEach(item => this.container.appendChild(item))
        this.setStyle();
        if (this.options.navigation) {
            this.createNavigation();
        }
        if (this.options.pagination) {
            this.createPagination();
        }

        // Evenements
        this.moveCallback.forEach(cb => cb(0))
        this.onWindowResize()
        window.addEventListener('resize', this.onWindowResize.bind(this))
        this.root.addEventListener('keyup', (evt) => {
            if (evt.key === 'ArrowRight' || evt.key === 'Right') {
                this.next()
            } else if (evt.key === 'ArrowLeft' || evt.key === 'Left') {
                this.prev()
            }
        })
        if (this.options.infinite) {
            this.container.addEventListener('transitionend', this.resetInfinite.bind(this))
        }
        // window.setTimeout(() => {
        //     this.next()
        // }, 2000);
        if (this.options.turnInfinite){
            window.setInterval(() => {
                this.next()
            }, 5000);
        }
        
    }

    /**
     * Applique les bonnes dimensions au éléments du carousel
     */
    setStyle () {
        let ratio = this.items.length / this.slidesVisible
        this.container.style.width = (ratio * 100) + '%'
        this.items.forEach(item => item.style.width = ((100 / this.slidesVisible) / ratio) + "%");
    }

    // crée les flèches dans le DOM
    createNavigation () {
        let nextButton = this.createDivWithClass('carousel__next');
        let prevButton = this.createDivWithClass('carousel__prev');
        this.root.appendChild(nextButton);
        this.root.appendChild(prevButton);
        nextButton.addEventListener('click', this.next.bind(this))
        prevButton.addEventListener('click', this.prev.bind(this))
        if (this.options.loop === true) {
            return
        }
        this.onMove(index => {
            if (index === 0) {
                prevButton.classList.add('carousel__prev--hidden')
            } else {
                prevButton.classList.remove('carousel__prev--hidden')
            }
            if (this.items[this.currentItem + this.slidesVisible] === undefined) {
                nextButton.classList.add('carousel__next--hidden')
            } else {
                nextButton.classList.remove('carousel__next--hidden')
            }
        })
    }

     // crée la pagination dans le DOM
    createPagination () {
        let pagination = this.createDivWithClass('carousel__pagination');
        let buttons = []
        this.root.appendChild(pagination)
        for (let i = 0; i < (this.items.length - 2 * this.offset); i = i + this.options.slidesToScroll) {
            let button = this.createDivWithClass('carousel__pagination__button')
            button.addEventListener('click', () => this.goToItem(i + this.offset))
            pagination.appendChild(button)
            buttons.push(button)
        }
        this.onMove(index => {
            let count = this.items.length - 2 * this.offset
            let activeButton = buttons[Math.floor((index - this.offset) % count / this.options.slidesToScroll)]
            if (activeButton) {
                buttons.forEach(button => button.classList.remove('carousel__pagination__button--active'))
                activeButton.classList.add('carousel__pagination__button--active')
            }
        })
    }

    next () {
        this.goToItem(this.currentItem + this.slidesToScroll)
    }

    prev () {
        this.goToItem(this.currentItem - this.slidesToScroll)
    }

    /**
     * Déplace le carousel vers l'élément ciblé
     * @param {number} index 
     * @param {Booleen} index [animation = true]
     */
    goToItem (index, animation = true) {
        if ( index < 0) {
            if (this.options.loop) {
                index = this.items.length - this.slidesVisible
            } else {
                return
            }
        } else if (index >= this.items.length || (this.items[this.currentItem + this.slidesVisible] === undefined && index > this.currentItem)) {
            if (this.options.loop) {
                index = 0;
            } else {
                return
            }
            
        }
        let translateX = index * -100 / this.items.length
        if (animation === false) {
            this.container.style.transition = 'none'
        }
        this.container.style.transform = 'translate3d(' + translateX + '%, 0, 0)'
        this.container.offsetHeight // force repaint
        if (animation === false) {
            this.container.style.transition = ''
        }
        this.currentItem = index;
        this.moveCallback.forEach(cb => cb(index))
    }

    /**
     * Déplace le container pour donner l'impression d'un slide infini
     */
    resetInfinite () {
        if (this.currentItem <= this.options.slidesToScroll) {
            this.goToItem(this.currentItem + ( this.items.length - 2 * this.offset), false)
        } else if (this.currentItem >= this.items.length - this.offset) {
            this.goToItem(this.currentItem - (this.items.length - 2 * this.offset), false)
        }
    }

    /**
     * 
     * @param {moveCallback} cb 
     */
    onMove (cb) {
        this.moveCallback.push(cb)
    }

    onWindowResize () {
        let mobile = window.innerWidth < 800
        if (mobile !== this.isMobile) {
            this.isMobile = mobile
            this.setStyle()
            this.moveCallback.forEach(cb => cb(this.currentItem))
        }
    }

    /**
     * @param {string} className
     * @returns {HTMLElement}
     */
    createDivWithClass (className) {
        let div =  document.createElement('div')
        div.setAttribute('class', className)
        return div
    }

    /**
     * @returns (number)
     */
    get slidesToScroll () {
        return this.isMobile ? 1 : this.options.slidesToScroll
    }

    /**
     * @returns (number)
     */
    get slidesVisible () {
        return this.isMobile ? 1 : this.options.slidesVisible
    }
}


if (document.getElementById('carousel1')) {
    document.addEventListener('DOMContentLoaded', function () {
        new Carousel(document.getElementById('carousel1'), {
            slidesToScroll: 1,
            slidesVisible: 3,
            // loop: true,
            pagination: false,
            // infinite: true,
            navigation: true,
            turnInfinite: true
        })
    })
}

if (document.getElementById('carousel2')) {
    document.addEventListener('DOMContentLoaded', function () {
        new Carousel(document.getElementById('carousel2'), {
            slidesToScroll: 1,
            slidesVisible: 2,
            // loop: true,
            pagination: false,
            // infinite: true,
            navigation: true,
            turnInfinite: true
        })
    })
}

if (document.getElementById('carousel3')) {
    document.addEventListener('DOMContentLoaded', function () {
        new Carousel(document.getElementById('carousel3'), {
            slidesToScroll: 1,
            slidesVisible: 2,
            // loop: true,
            pagination: false,
            // infinite: true,
            navigation: true,
            turnInfinite: true
        })
    })
}