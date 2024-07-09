import React from "react";
import InputSection from "./elements/InputSection";
export default function ContentChat({addMessage}){

    return (
        <>
            <div className="contentChat">

                <div className="messages">
                </div>
                <InputSection addMessage={addMessage}></InputSection>
            </div>
        </>
    )
}