import { API_URL } from "@/constants";
import axios from "axios";


const token = window.localStorage.getItem("token");
export function login(email, senha) {
    const data = {
        email: email,
        password: senha,
    };
    return axios.post(`${API_URL}auth/login`, data);
}

