const BASE_URL = 'http://127.0.0.1:8000/api';

function getToken() {
    return localStorage.getItem('token');
}

function setToken(token) {
    localStorage.setItem('token', token);
}

function removeToken() {
    localStorage.removeItem('token');
}

function getUser() {
    return JSON.parse(localStorage.getItem('user') || 'null');
}

function setUser(user) {
    localStorage.setItem('user', JSON.stringify(user));
}

function removeUser() {
    localStorage.removeItem('user');
}

async function apiRequest(endpoint, method = 'GET', body = null, auth = false) {
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    };

    if (auth) {
        headers['Authorization'] = `Bearer ${getToken()}`;
    }

    const options = { method, headers };
    if (body) options.body = JSON.stringify(body);

    const response = await fetch(`${BASE_URL}${endpoint}`, options);
    const data = await response.json();

    if (!response.ok) {
        throw { status: response.status, data };
    }

    return data;
}
