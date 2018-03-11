import * as types from '../mutations-types.js'

//initialState
const state = {
    user: {},
    token: localStorage.getItem('token'),
};

//mutations
const mutations = {
    [types.SET_AUTH_TOKEN](state, token) {
        state.token = token;
        localStorage.setItem('token', token)
    },
    [types.SET_AUTH_USER](state, {user}) {
        state.user = user;
    }
};

//actions
const actions = {
    /* context {state //store.state, commit, //store.commit dispatch, //store.dispatch }
       ex: func(context){ context.commit()}
           func({commit,dispatch}){ commit() dispatch()}
     */
    setToken({commit}, token) {
        commit('SET_AUTH_TOKEN', token);
    },
    fetchAuthUser({commit}) {
        return new Promise((resolve, reject) => {
            axios.get('/me')
                .then((response) => {
                    commit('SET_AUTH_USER', response.data);
                    resolve();
                })
                .catch((error) => {
                    reject(error);
                })
        });
    }
};

//getters
const getters = {
    authUser: state => state.user, //state => { return state.user }
    authToken: state => state.token,
    isAuthenticated: state => state.user !== null
};

export default {
    state,
    mutations,
    actions,
    getters
}