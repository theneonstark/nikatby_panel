"use client"

import { motion } from "framer-motion"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Badge } from "@/components/ui/badge"
import { Fuel, Calendar, MapPin, Phone } from "lucide-react"

const lpgProviders = ["Indane Gas", "Bharat Gas", "HP Gas", "Reliance Gas", "Jio-bp Gas"]

const recentBookings = [
  {
    id: "LPG001",
    provider: "Indane Gas",
    consumerNo: "12345678901",
    amount: "$15",
    status: "Delivered",
    date: "2024-01-15",
  },
  {
    id: "LPG002",
    provider: "Bharat Gas",
    consumerNo: "23456789012",
    amount: "$15",
    status: "Booked",
    date: "2024-01-14",
  },
  {
    id: "LPG003",
    provider: "HP Gas",
    consumerNo: "34567890123",
    amount: "$15",
    status: "Out for Delivery",
    date: "2024-01-13",
  },
]

const deliverySlots = ["9:00 AM - 12:00 PM", "12:00 PM - 3:00 PM", "3:00 PM - 6:00 PM", "6:00 PM - 9:00 PM"]

export function LPGContent() {
  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.5 }}
      className="space-y-6"
    >
      <div>
        <h1 className="text-3xl font-bold text-foreground mb-2">LPG Booking & Payment</h1>
        <p className="text-muted-foreground">Book LPG cylinders and make payments online</p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Total Bookings</CardTitle>
            <Fuel className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">1,234</div>
            <p className="text-xs text-muted-foreground">This month</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Delivered</CardTitle>
            <Calendar className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">1,156</div>
            <p className="text-xs text-muted-foreground">93.7% success rate</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Pending</CardTitle>
            <MapPin className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">78</div>
            <p className="text-xs text-muted-foreground">In process</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Revenue</CardTitle>
            <Phone className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">$18,510</div>
            <p className="text-xs text-muted-foreground">This month</p>
          </CardContent>
        </Card>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card>
          <CardHeader>
            <CardTitle>Book LPG Cylinder</CardTitle>
            <CardDescription>Book a new LPG cylinder for delivery</CardDescription>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="lpg-provider">LPG Provider</Label>
              <Select>
                <SelectTrigger>
                  <SelectValue placeholder="Select LPG provider" />
                </SelectTrigger>
                <SelectContent>
                  {lpgProviders.map((provider) => (
                    <SelectItem key={provider} value={provider.toLowerCase().replace(/\s+/g, "-")}>
                      {provider}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div className="space-y-2">
              <Label htmlFor="consumer-number">Consumer Number</Label>
              <Input id="consumer-number" placeholder="Enter 11-digit consumer number" />
            </div>
            <div className="space-y-2">
              <Label htmlFor="mobile-number">Mobile Number</Label>
              <Input id="mobile-number" placeholder="Enter registered mobile number" />
            </div>
            <div className="space-y-2">
              <Label htmlFor="delivery-date">Preferred Delivery Date</Label>
              <Input id="delivery-date" type="date" />
            </div>
            <div className="space-y-2">
              <Label htmlFor="delivery-slot">Delivery Time Slot</Label>
              <Select>
                <SelectTrigger>
                  <SelectValue placeholder="Select time slot" />
                </SelectTrigger>
                <SelectContent>
                  {deliverySlots.map((slot) => (
                    <SelectItem key={slot} value={slot}>
                      {slot}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div className="space-y-2">
              <Label htmlFor="address">Delivery Address</Label>
              <Input id="address" placeholder="Enter delivery address" />
            </div>
            <Button className="w-full">Book Cylinder ($15)</Button>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Recent Bookings</CardTitle>
            <CardDescription>Your recent LPG cylinder bookings</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {recentBookings.map((booking) => (
                <div key={booking.id} className="flex items-center justify-between p-3 border rounded-lg">
                  <div>
                    <p className="font-medium">{booking.consumerNo}</p>
                    <p className="text-sm text-muted-foreground">
                      {booking.provider} • {booking.date}
                    </p>
                  </div>
                  <div className="text-right">
                    <p className="font-medium">{booking.amount}</p>
                    <Badge
                      variant={
                        booking.status === "Delivered"
                          ? "default"
                          : booking.status === "Booked"
                            ? "secondary"
                            : "outline"
                      }
                    >
                      {booking.status}
                    </Badge>
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>LPG Subsidy Information</CardTitle>
          <CardDescription>Important information about LPG subsidies</CardDescription>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div className="space-y-4">
              <h3 className="font-medium">Subsidy Eligibility</h3>
              <ul className="text-sm text-muted-foreground space-y-2">
                <li>• Valid Aadhaar card linked to LPG connection</li>
                <li>• Bank account linked for direct benefit transfer</li>
                <li>• Maximum 12 subsidized cylinders per year</li>
                <li>• Income criteria as per government guidelines</li>
              </ul>
            </div>
            <div className="space-y-4">
              <h3 className="font-medium">How Subsidy Works</h3>
              <ul className="text-sm text-muted-foreground space-y-2">
                <li>• Pay full price at the time of booking</li>
                <li>• Subsidy amount credited to bank account</li>
                <li>• Usually takes 2-3 working days for credit</li>
                <li>• SMS notification sent after credit</li>
              </ul>
            </div>
          </div>
        </CardContent>
      </Card>
    </motion.div>
  )
}
