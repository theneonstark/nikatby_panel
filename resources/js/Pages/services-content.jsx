"use client"

import { motion } from "framer-motion"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { Zap, Shield, Smartphone, Building, Search } from "lucide-react"

const services = [
  {
    icon: Zap,
    title: "Recharge Services",
    description: "Mobile, DTH, and data card recharge services",
    status: "Active",
    transactions: "1,234",
    revenue: "$12,450",
  },
  {
    icon: Shield,
    title: "Bill Payment",
    description: "Electricity, water, gas, and other utility payments",
    status: "Active",
    transactions: "856",
    revenue: "$8,920",
  },
  {
    icon: Smartphone,
    title: "CMS Services",
    description: "Cash management and banking services",
    status: "Active",
    transactions: "432",
    revenue: "$15,680",
  },
  {
    icon: Building,
    title: "Government Services",
    description: "Municipality payments and government services",
    status: "Active",
    transactions: "298",
    revenue: "$5,430",
  },
  {
    icon: Search,
    title: "Status Enquiry",
    description: "Transaction status and enquiry services",
    status: "Active",
    transactions: "2,156",
    revenue: "$3,210",
  },
]

export function ServicesContent() {
  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.5 }}
      className="space-y-6"
    >
      <div>
        <h1 className="text-3xl font-bold text-foreground mb-2">Services Management</h1>
        <p className="text-muted-foreground">Manage and monitor all available services</p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {services.map((service, index) => (
          <motion.div
            key={service.title}
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: index * 0.1 }}
          >
            <Card className="hover:shadow-lg transition-shadow">
              <CardHeader>
                <div className="flex items-center justify-between">
                  <service.icon className="w-8 h-8 text-primary" />
                  <Badge variant="secondary">{service.status}</Badge>
                </div>
                <CardTitle className="text-lg">{service.title}</CardTitle>
                <CardDescription>{service.description}</CardDescription>
              </CardHeader>
              <CardContent>
                <div className="space-y-2">
                  <div className="flex justify-between text-sm">
                    <span className="text-muted-foreground">Transactions:</span>
                    <span className="font-medium">{service.transactions}</span>
                  </div>
                  <div className="flex justify-between text-sm">
                    <span className="text-muted-foreground">Revenue:</span>
                    <span className="font-medium text-green-600">{service.revenue}</span>
                  </div>
                </div>
                <div className="flex gap-2 mt-4">
                  <Button size="sm" className="flex-1">
                    Configure
                  </Button>
                  <Button size="sm" variant="outline" className="flex-1 bg-transparent">
                    View Details
                  </Button>
                </div>
              </CardContent>
            </Card>
          </motion.div>
        ))}
      </div>
    </motion.div>
  )
}
