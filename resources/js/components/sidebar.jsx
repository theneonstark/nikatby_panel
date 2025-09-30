"use client"

import { useState } from "react"
import { motion, AnimatePresence } from "framer-motion"
import {
  Home,
  Settings,
  CreditCard,
  Zap,
  Bus,
  ChevronRight,
  Menu,
  X,
  Smartphone,
  Lightbulb,
  Shield,
  Fuel,
  Building,
  Search,
} from "lucide-react"
import { Button } from "@/components/ui/button"
import { cn } from "@/lib/utils"

const menuItems = [
  { icon: Home, label: "Dashboard", href: "/", key: "dashboard" },
  { icon: Settings, label: "Services", href: "/services", key: "services" },
  { icon: CreditCard, label: "Payout", href: "/payout", key: "payout" },
  { icon: Zap, label: "Recharge", href: "/recharge", key: "recharge" },
  {
    icon: Bus,
    label: "Bus Booking",
    href: "/bus-booking",
    key: "bus-booking",
    submenu: [
      { label: "Get Available Trip", href: "/bus-booking/available-trip" },
      { label: "Get Current Trip Details", href: "/bus-booking/trip-details" },
      { label: "Get Boarding Point Details", href: "/bus-booking/boarding-points" },
      { label: "Reserve Tickets", href: "/bus-booking/reserve" },
      { label: "Book Tickets", href: "/bus-booking/book" },
      { label: "Check Booked Tickets", href: "/bus-booking/check" },
      { label: "Ticket Cancellation", href: "/bus-booking/cancel" },
    ],
  },
  { icon: Smartphone, label: "CMS-Airtel", href: "/cms-airtel", key: "cms-airtel" },
  { icon: Lightbulb, label: "Electricity Bill Payment", href: "/electricity", key: "electricity" },
  { icon: Shield, label: "Insurance Payment", href: "/insurance", key: "insurance" },
  { icon: Fuel, label: "Fastag Recharge", href: "/fastag", key: "fastag" },
  { icon: Fuel, label: "LPG Booking & Payment", href: "/lpg", key: "lpg" },
  { icon: Building, label: "Municipality Payment", href: "/municipality", key: "municipality" },
  { icon: Search, label: "Status Enquiry", href: "/status", key: "status" },
]

export function Sidebar({ collapsed, onToggle, activePage, onPageChange }) {
  const [expandedItems, setExpandedItems] = useState(["Bus Booking"])

  const toggleSubmenu = (label) => {
    setExpandedItems((prev) => (prev.includes(label) ? prev.filter((item) => item !== label) : [...prev, label]))
  }

  const handleMenuClick = (item) => {
    if (item.submenu) {
      toggleSubmenu(item.label)
    } else {
      onPageChange(item.key)
    }
  }

  return (
    <motion.div
      initial={false}
      animate={{ width: collapsed ? 80 : 280 }}
      transition={{ duration: 0.3, ease: "easeInOut" }}
      className="bg-sidebar border-r border-sidebar-border h-screen sticky top-0 flex flex-col"
    >
      <div className="p-4 border-b border-sidebar-border">
        <div className="flex items-center justify-between">
          <AnimatePresence mode="wait">
            {!collapsed && (
              <motion.div
                initial={{ opacity: 0, x: -20 }}
                animate={{ opacity: 1, x: 0 }}
                exit={{ opacity: 0, x: -20 }}
                transition={{ duration: 0.2 }}
                className="flex items-center gap-2"
              >
                <div className="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                  <Home className="w-4 h-4 text-primary-foreground" />
                </div>
                <span className="font-semibold text-sidebar-foreground">Admin Panel</span>
              </motion.div>
            )}
          </AnimatePresence>
          <Button
            variant="ghost"
            size="icon"
            onClick={onToggle}
            className="text-sidebar-foreground hover:bg-sidebar-accent"
          >
            {collapsed ? <Menu className="w-4 h-4" /> : <X className="w-4 h-4" />}
          </Button>
        </div>
      </div>

      <nav className="flex-1 p-4 space-y-2 overflow-y-auto">
        {menuItems.map((item) => (
          <div key={item.label}>
            <Button
              variant="ghost"
              className={cn(
                "w-full justify-start text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground",
                collapsed ? "px-2" : "px-3",
                activePage === item.key && "bg-sidebar-accent text-sidebar-accent-foreground",
              )}
              onClick={() => handleMenuClick(item)}
            >
              <item.icon className={cn("w-4 h-4", collapsed ? "" : "mr-3")} />
              <AnimatePresence mode="wait">
                {!collapsed && (
                  <motion.span
                    initial={{ opacity: 0, x: -10 }}
                    animate={{ opacity: 1, x: 0 }}
                    exit={{ opacity: 0, x: -10 }}
                    transition={{ duration: 0.2 }}
                    className="flex-1 text-left"
                  >
                    {item.label}
                  </motion.span>
                )}
              </AnimatePresence>
              {item.submenu && !collapsed && (
                <motion.div
                  animate={{ rotate: expandedItems.includes(item.label) ? 90 : 0 }}
                  transition={{ duration: 0.2 }}
                >
                  <ChevronRight className="w-4 h-4" />
                </motion.div>
              )}
            </Button>

            <AnimatePresence>
              {item.submenu && expandedItems.includes(item.label) && !collapsed && (
                <motion.div
                  initial={{ opacity: 0, height: 0 }}
                  animate={{ opacity: 1, height: "auto" }}
                  exit={{ opacity: 0, height: 0 }}
                  transition={{ duration: 0.3 }}
                  className="ml-6 mt-2 space-y-1 overflow-hidden"
                >
                  {item.submenu.map((subItem) => (
                    <Button
                      key={subItem.label}
                      variant="ghost"
                      size="sm"
                      className="w-full justify-start text-sidebar-muted-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground"
                    >
                      <span className="text-sm">{subItem.label}</span>
                    </Button>
                  ))}
                </motion.div>
              )}
            </AnimatePresence>
          </div>
        ))}
      </nav>
    </motion.div>
  )
}
