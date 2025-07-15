import { createStore } from 'vuex';
import axios from "@/axios.js";

export default createStore({
    state: {
        token: localStorage.getItem('token') || '',
        isAuthenticated: false,
        isSidebarVisible: '',
        breadcrum: 'dashboard'
    },

    mutations: {
        updateAuthStatus(state, status){
            state.isAuthenticated = status
        },

        updateAuthToken(state, token){
            state.token = token;
            if(state.token){
                localStorage.setItem('token', token)
            } else {
                localStorage.removeItem('token')
            }
        },

        updateAuthenticationStatus(state, status){
            state.isAuthenticated = status
        },

        logoutUser(state){
            state.token = null
            state.isAuthenticated = false
            console.log(state.token)
            console.log(state.isAuthenticated)
        },

    //     Toggle menu button
        toggleSidebar(state){
            state.isSidebarVisible = !state.isSidebarVisible;
        },

        setSidebarVisible(state, isVisible){
            state.isSidebarVisible = isVisible;
        },

    //     change Breadcrum
        setBreadcrum(state, value){
            state.breadcrum = value
        }
    },

    actions: {
        checkAuthStatus( {commit}, status){
            commit('updateAuthStatus', status)
        },

        setAuthToken( {commit}, token ){
            commit('updateAuthToken', token)
        },

        checkUserAuthentication({commit}){
            axios.get('api/checkAuthStatus')
                .then((response)=>{
                    console.log("checkUserAuthentication state vuex: ")
                    console.log(response.data.status)
                    commit('updateAuthenticationStatus', response.data.status);
                }).catch((error)=>{
                console.error(error);
            })
        },

        logout({commit}){
            commit('logoutUser')
            localStorage.removeItem('token')
            delete axios.defaults.headers.common['Authorization']
        },

    //     toggle menu button
        toggleSidebar({commit}) {
            commit('toggleSidebar')
        },

        setSidebarVisible({commit}, isVisible){
            commit('setSidebarVisible', isVisible)
        },

    //     Change Breadcrum
        setBreadcrum({commit}, value){
            commit('setBreadcrum', value)
        }
    },

    getters: {
        authToken: state => state.token,
        authStatus: state => state.isAuthenticated
    }
})
