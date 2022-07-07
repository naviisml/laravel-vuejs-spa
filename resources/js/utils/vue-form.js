import app from '../app'
import axios from 'axios'

class VueForm
{
    /**
     * The vue form constructor
     *
     * @param {object} formData
     */
    constructor( formData ) {
        for (const [key, value] of Object.entries(formData)) {
            this[key] = value
        }

        this.errors = []
    }

    /**
     * Send a POST request
     *
     * @param  {string}  url
     * @return {Promise}
     */
    async post( url ) {
        let response = await axios.post(url, this)

        this.parseRequest( response )

        return this
    }

    /**
     * Send a PATCH request
     *
     * @param  {string}  url
     * @return {Promise}
     */
    async patch( url ) {
        let response = await axios.patch(url, this)

        this.parseRequest( response )

        return this
    }

    /**
     * Send a PATCH request
     *
     * @param  {string}  url
     * @return {Promise}
     */
    async delete( url ) {
        let response = await axios.delete(url, this)

        this.parseRequest( response )

        return this
    }

    /**
     * Send a GET request
     *
     * @param  {string}  url
     * @return {Promise}
     */
    async get( url ) {
        let response = await axios.get(url, this)

        this.parseRequest( response )

        return this
    }

    /**
     * Parse the request data
     *
     * @param  {object} response
     * @return {null}
     */
    parseRequest( response ) {
        const { data, status } = response

        this.data = data
        this.status = status
        this.errors = []

        if( this.data.errors ) {
            Object.keys(this.data.errors).map( (error, index) => this.errors[error] = this.data.errors[error][0] )
        }
    }

	/**
	 * Check wether the field contains a error
	 *
	 * @param   {string}  field
	 *
	 * @return  {object}
	 */
    hasError( field ) {
        if( !this.errors || !this.errors[field] ) {
            return false
        }

        return {
            message: this.errors[field]
        }
    }
}

export default VueForm
