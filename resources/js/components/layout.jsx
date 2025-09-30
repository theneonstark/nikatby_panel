import { useState } from "react"
import { Sidebar } from "@/components/sidebar"
import { Header } from "@/components/header" 
import { DashboardContent } from "@/components/dashboard-content" 
import { ServicesContent } from "./services-content" 
import { PayoutContent } from "./payout-content" 
import { RechargeContent } from "./recharge-content" 
import { BusBookingContent } from "./bus-booking-content" 
import { CMSAirtelContent } from "./cms-airtel-content" 
import { ElectricityContent } from "./electricity-content" 
import { InsuranceContent } from "./insurance-content" 
import { FastagContent } from "./fastag-content" 
import { LPGContent } from "./lpg-content" 
import { MunicipalityContent } from "./municipality-content" 
import { StatusContent } from "./status-content" 
import { Head } from "@inertiajs/react"


export default function Layout({children, title}) {
  const [sidebarCollapsed, setSidebarCollapsed] = useState(false)
  const [activePage, setActivePage] = useState("dashboard")

  // const renderContent = () => {
  //   switch (activePage) {
  //     case "dashboard":
  //       return <DashboardContent />
  //     case "services":
  //       return <ServicesContent />
  //     case "payout":
  //       return <PayoutContent />
  //     case "recharge":
  //       return <RechargeContent />
  //     case "bus-booking":
  //       return <BusBookingContent />
  //     case "cms-airtel":
  //       return <CMSAirtelContent />
  //     case "electricity":
  //       return <ElectricityContent />
  //     case "insurance":
  //       return <InsuranceContent />
  //     case "fastag":
  //       return <FastagContent />
  //     case "lpg":
  //       return <LPGContent />
  //     case "municipality":
  //       return <MunicipalityContent />
  //     case "status":
  //       return <StatusContent />
  //     default:
  //       return <DashboardContent />
  //   }
  // }

  return (
    <>
    <Head title={title}/>
      <div className="min-h-screen bg-background dark">
      <div className="flex">
        <Sidebar
          collapsed={sidebarCollapsed}
          onToggle={() => setSidebarCollapsed(!sidebarCollapsed)}
          activePage={activePage}
          onPageChange={setActivePage}
        />
        <div className="flex-1 flex flex-col">
          <Header />
          <main className="flex-1 p-6">{children}</main>
        </div>
      </div>
    </div>
    </>
  )
}
