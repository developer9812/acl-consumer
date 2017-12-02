import store from '../store'

export default class Auth {
  static isAuthenticated(){
    return new Promise((resolve, reject) => {
      axios.get('/api/auth/status')
        .then(response => {
          console.log("AUTH STATUS");
          console.log(response);
          if (response.data.status) {
            store.commit('setLoginStatus', true);
            resolve(true);
          } else {
            store.commit('setLoginStatus', false);
            resolve(false);
          }
        })
        .catch(error => {
          console.log("ERROR");
          console.log(error);
          store.commit('setLoginStatus', false);
          resolve(false);
        })
    })
  }
}
