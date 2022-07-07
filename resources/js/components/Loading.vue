<template>
    <div :style="{width: `${percent}%`, height: height, opacity: show ? 1 : 0, 'background-color': canSuccess ? color : failedColor}" class="progress">
        <div class="progress-spinner"><div></div><div></div><div></div><div></div></div>
    </div>
</template>

<script>
    export default {
        data: () => ({
            percent: 0,
            show: false,
            canSuccess: true,
            duration: 3000,
            height: '2px',
            color: 'rgb(var(--color-dark))',
            failedColor: 'red'
        }),

        computed: {
            loading () {
                return this.$store.state.loading.state
            }
        },

        methods: {
            start () {
                this.show = true
                this.canSuccess = true

                if (this._timer) {
                    clearInterval(this._timer)
                    this.percent = 0
                }

                this._cut = 10000 / Math.floor(this.duration)
                this._timer = setInterval(() => {
                    this.increase(this._cut * Math.random())

                    if (this.percent > 95) {
                        this.finish()
                    }
                }, 100)

                return this
            },
            set (num) {
                this.show = true
                this.canSuccess = true
                this.percent = Math.floor(num)

                return this
            },
            get () {
                return Math.floor(this.percent)
            },
            increase (num) {
                this.percent = this.percent + Math.floor(num)

                return this
            },
            decrease (num) {
                this.percent = this.percent - Math.floor(num)

                return this
            },
            finish () {
                this.percent = 100
                this.hide()

                return this
            },
            pause () {
                clearInterval(this._timer)

                return this
            },
            hide () {
                clearInterval(this._timer)
                this._timer = null

                setTimeout(() => {
                    this.show = false
                    this.$nextTick(() => {
                        setTimeout(() => {
                            this.percent = 0
                        }, 200)
                    })
                }, 500)

                return this
            },
            fail () {
                this.canSuccess = false

                return this
            }
        },

        watch: {
            loading (value) {
                return value ? this.start() : this.finish()
            }
        }
    }
</script>

<style scoped>
.progress {
    position: fixed;
    top: 0px;
    left: 0px;
    right: 0px;
    height: 2px;
    width: 0%;
    transition: width 0.2s, opacity 0.4s;
    opacity: 1;
    background-color: #efc14e;
    z-index: 999999;
}
.progress-spinner {
    position: fixed;
    width: 20px;
    height: 20px;
    right: 5px;
    top: 5px;
}
.progress-spinner div {
    box-sizing: border-box;
    display: block;
    position: absolute;
    width: 100%;
    height: 100%;
    margin: 2px;
    border: 2px solid rgb(var(--color-dark));
    border-radius: 50%;
    animation: progress-spinner 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    border-color: rgb(var(--color-dark)) transparent transparent transparent;
}
.progress-spinner div:nth-child(1) {
    animation-delay: -0.45s;
}
.progress-spinner div:nth-child(2) {
    animation-delay: -0.3s;
}
.progress-spinner div:nth-child(3) {
    animation-delay: -0.15s;
}
@keyframes progress-spinner {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
