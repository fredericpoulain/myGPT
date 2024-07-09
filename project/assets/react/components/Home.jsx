import React, {useState} from "react";
import {UseFetchChat} from "./UseFetchChat";
import ContentChat from "./ContentChat";
import NavMobile from "./elements/NavMobile";
import SideBar from "./elements/SideBar";
import {fetchDataFromServer} from "./utils/functions";
export function Home() {

    const [chat, setChat] = useState(null)
    const URL_GET_CHAT = '/getChat';
    const URL_ADD_CHAT = '/addChat';
    const {loading, errors} = UseFetchChat(URL_GET_CHAT,setChat);
    // const [messages, setMessages] = useState([])

    console.log(chat)

    const addMessage = async (e) => {
        e.preventDefault();
        const elTextarea = document.querySelector("textarea");
        const message = elTextarea.value;
        // setMessages(prevMessages => [...prevMessages, message]);
        elTextarea.value = '';
        const object = {
            'message': message
        };
        const result = await fetchDataFromServer(object, URL_ADD_CHAT, 'POST');
        const messages = result.data.messages;
        // setChat(messages);
        setChat(prevChat => {
            const updatedChat = [...prevChat];
            prevChat.push(messages.at(-1))
            return updatedChat;
        });
        console.log(chat);

    };


    return <>
        {loading && 'chargement...!'}
        {chat && <div>
            <NavMobile></NavMobile>
            <div className="main">
                <SideBar/>
                <ContentChat addMessage={addMessage}/>
            </div>
        </div>
        }
        {errors && <div>{errors}</div>}
    </>
}
