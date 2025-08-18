import { createStore } from 'vuex';
import axios from "@/axios.js";

export default createStore({
    state: {
        token: localStorage.getItem('token') || '',
        isAuthenticated: false,
        isSidebarVisible: '',
        breadcrum: 'dashboard',
        roles: [],
        permissions: []
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
            state.token = null;
            state.isAuthenticated = false;
            state.user = null;
            state.roles = [];
            state.permissions = [];
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
        },

    //     check role and permissions
        setUserData(state, {user, roles, permissions}){
            state.user =  user;
            state.roles = roles;
            state.permissions = permissions;
        },
    },

    actions: {
        checkAuthStatus( {commit}, status){
            commit('updateAuthStatus', status)
        },

        setAuthToken( {commit}, token ){
            commit('updateAuthToken', token)
        },

        async fetchUserData({commit}){
            try{
                const res = await axios.get('api/me');
                commit('setUserData', {
                    user: res.data.user,
                    roles: res.data.roles,
                    permissions: res.data.permissions
                });
                commit('updateAuthStatus', true);
            }catch(error){
                commit('updateAuthStatus', false);
            }
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
        authStatus: state => state.isAuthenticated,
        user: state => state.user,
        roles: state => state.roles,
        permissions: state => state.permissions,
        can: state => (perm) => state.permissions.includes(perm),
        hasRole: state => (role) => state.roles.includes(role)
    }
})
