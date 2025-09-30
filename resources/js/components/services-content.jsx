"use client"

import { motion } from "framer-motion"
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { Zap, Droplet, Phone, Tv, CreditCard } from "lucide-react"
import { Link } from "@inertiajs/react"

export function ServicesContent({ bbps_service }) {
  // Mapping of service names to icons and background colors
  const serviceStyles = {
    Electricity: { icon: Zap, bgColor: "bg-blue-50", description: "Pay electricity bills", status: "Active", transactions: 1200, revenue: "$5000" },
    Water: { icon: Droplet, bgColor: "bg-green-50", description: "Pay water bills", status: "Active", transactions: 800, revenue: "$3000" },
    Telephone: { icon: Phone, bgColor: "bg-purple-50", description: "Pay telephone bills", status: "Active", transactions: 600, revenue: "$2000" },
    Broadband: { icon: Tv, bgColor: "bg-orange-50", description: "Pay broadband bills", status: "Active", transactions: 400, revenue: "$1500" },
    "Credit Card": { icon: CreditCard, bgColor: "bg-red-50", description: "Pay credit card bills", status: "Active", transactions: 1000, revenue: "$4000" },
    // Default for unknown services
    default: { icon: CreditCard, bgColor: "bg-gray-50", description: "Pay bills", status: "Active", transactions: 500, revenue: "$1000" },
  };

  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.5 }}
      className="space-y-6"
    >
      <div>
        <h1 className="text-3xl font-bold text-foreground mb-2">BBPS Services</h1>
        <p className="text-muted-foreground">Manage and monitor all available services</p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {bbps_service.map((serviceName, index) => {
          const service = serviceStyles[serviceName] || serviceStyles.default;
          const IconComponent = service.icon;

          return (
            <Link href={`/bbps/service/${serviceName}`}>
              <motion.div
              key={serviceName}
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.5, delay: index * 0.1 }}
            >
              <Card className={`hover:shadow-lg transition-shadow ${service.bgColor}`}>
                <CardHeader>
                  <div className="flex items-center justify-between">
                    <IconComponent className="w-8 h-8 text-primary" />
                    <Badge variant="secondary">{service.status}</Badge>
                  </div>
                  <CardTitle className="text-lg">{serviceName}</CardTitle>
                  <CardDescription>{service.description}</CardDescription>
                </CardHeader>
                {/* <CardContent>
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
                </CardContent> */}
              </Card>
            </motion.div>
            </Link>
          );
        })}
      </div>
    </motion.div>
  )
}