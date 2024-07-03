import Vue from 'vue'
import Vuex from 'vuex'
import axios from "axios";

Vue.use(Vuex)

axios.defaults.baseURL = 'http://localhost:8000/api'

export default new Vuex.Store({
  state: {
      token: localStorage.getItem('token') || null
  },
  getters: {
      loggedIn(state) {
          return state.token !== null
      }
  },
  mutations: {
      setToken(state, token) {
          state.token = token
      },
      removeToken(state) {
          state.token = null
      }
  },
  actions: {
      register(context, form) {
          return new Promise((resolve, reject)=> {
              axios.post('/register', {
                  name: form.name,
                  email: form.email,
                  password: form.password
              }).then(res=> {
                  resolve(res)
              }).catch(error=>{
                  reject(error)
              })
          })
      },
      login(context, form) {
          return new Promise((resolve, reject)=> {
              axios.post('/login', {
                  email: form.email,
                  password: form.password
              }).then(res=> {
                  localStorage.setItem('token', res.data.token)
                  context.commit('setToken', res.data.token)
                  resolve(res)
              }).catch(error=>{
                  reject(error)
              })
          })
      },
      logout(context) {
          axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token
          return new Promise((resolve, reject)=> {
              axios.post('/logout').then(res=> {
                  localStorage.removeItem('token')
                  context.commit('removeToken')
                  resolve(res)
              }).catch(error=>{
                  reject(error)
              })
          })
      }
  },
  modules: {
  }
})
