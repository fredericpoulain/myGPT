import React from "react";
import InputSection from "./elements/InputSection";
import MessageList from "./elements/MessageList";

export default function ContentChat({ isAddingMessage, chat, addMessage }) {
    return (
        <div className="contentChat">
            <MessageList isAddingMessage={isAddingMessage} messages={chat} />
            <InputSection addMessage={addMessage} />
        </div>
    );
}
