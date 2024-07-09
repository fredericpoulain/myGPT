import React from "react";

export default function SideBar(){

    return (
        <>
            <div className="sidenav">
                <div className="sidenavContent">
                    <button className="close"><span></span></button>
                    <div className="mainSidebar">
                        <button className="newChatBtn">
                            <i className="fa-regular fa-comments"></i> Nouveau
                        </button>
                        <div className="savedChats">
                            <h2>Historique</h2>
                            <a>Mes composants react ne sont pas chargés ..; j'utilise symfony ux</a>
                            <a>Mes composants react ne sont pas chargés ..; j'utilise symfony ux</a>


                        </div>

                    </div>

                    <div className="resetSession">
                        <hr/>
                        <a href="/resetSession">Reset</a>
                    </div>
                </div>
            </div>
        </>
    )
}