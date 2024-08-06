import React, {useEffect, useState} from "react";
import InputSection from "./elements/InputSection";
import MessageList from "./elements/MessageList";
import Scroll from "./elements/svg/Scroll";

export default function ContentChat({isAddingMessage, chat, addMessage, canScroll, setCanScroll, selectedModel, setSelectedModel, setRole}) {



    const handleSelectChange = (event) => {
        setSelectedModel(event.target.value);
    };
    const handleRoleChange = (event) => {
        setRole(event.target.value);
    };

    const [showScroll, setShowScroll] = useState(false);

    useEffect(() => {
        const checkScroll = () => {
            if (document.documentElement.scrollHeight > window.innerHeight) {
                setShowScroll(true);
                setCanScroll(true);
            } else {
                setShowScroll(false);
                setCanScroll(false);
            }
        };

        const handleScroll = () => {
            const scrollPosition = window.innerHeight + window.scrollY;
            const threshold = document.documentElement.scrollHeight - 50;

            if (scrollPosition >= threshold) {
                setShowScroll(false);
                setCanScroll(false);
            } else {
                setShowScroll(true);
                setCanScroll(true);
            }
        };

        checkScroll();  // Initial check
        window.addEventListener('resize', checkScroll);
        window.addEventListener('scroll', handleScroll);

        return () => {
            window.removeEventListener('resize', checkScroll);
            window.removeEventListener('scroll', handleScroll);
        };
    }, [chat, setCanScroll]);

    return (
        <div className="contentChat">
            <div className="headerChat">
                <select name="format" id="format" value={selectedModel} onChange={handleSelectChange}>
                    <option value="gpt-4o">GPT-4o</option>
                    <option value="gpt-4-turbo">GPT-4-turbo</option>
                    <option value="gpt-3.5-turbo">GPT-3.5-turbo</option>
                    <option value="dall-e-3">Dall-e-3</option>
                </select>
                <input onChange={handleRoleChange} className="headerChatInput" type="text" placeholder="Rôle IA par défaut : 'vous êtes un assistant' (à modifier selon vos préférences)"/>

            </div>

            <MessageList isAddingMessage={isAddingMessage} messages={chat}/>
            {showScroll && !isAddingMessage && canScroll && <Scroll/>}
            <InputSection addMessage={addMessage}/>
        </div>
    );
}
