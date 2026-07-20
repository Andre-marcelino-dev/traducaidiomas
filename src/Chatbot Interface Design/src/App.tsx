import { useState, useRef, useEffect, type KeyboardEvent } from 'react'

// ─── Types ─────────────────────────────────────────────────────────────────────

type View = 'initial' | 'chat'

interface Message {
  id: string
  role: 'user' | 'ai'
  text: string
  time: string
  card?: CardMessage
}

interface CardMessage {
  type: 'schedule'
  items: { day: string; date: string; subject: string; teacher: string; time: string; level: string }[]
}

// ─── Data ──────────────────────────────────────────────────────────────────────

const QUICK_ACTIONS = [
  { id: 'aulas', icon: '📅', label: 'Minhas aulas', color: 'blue' },
  { id: 'horarios', icon: '🕒', label: 'Consultar horários', color: 'violet' },
  { id: 'materiais', icon: '📚', label: 'Materiais de estudo', color: 'indigo' },
  { id: 'agendar', icon: '📝', label: 'Agendar aula', color: 'purple' },
  { id: 'professor', icon: '👨‍🏫', label: 'Contatar professor', color: 'blue' },
]

const ACTION_COLORS: Record<string, string> = {
  blue: 'bg-blue-50 border-blue-100 text-blue-700 hover:bg-blue-100 hover:border-blue-200',
  violet: 'bg-violet-50 border-violet-100 text-violet-700 hover:bg-violet-100 hover:border-violet-200',
  indigo: 'bg-indigo-50 border-indigo-100 text-indigo-700 hover:bg-indigo-100 hover:border-indigo-200',
  purple: 'bg-purple-50 border-purple-100 text-purple-700 hover:bg-purple-100 hover:border-purple-200',
}

const ACTION_ICON_COLORS: Record<string, string> = {
  blue: 'bg-blue-100',
  violet: 'bg-violet-100',
  indigo: 'bg-indigo-100',
  purple: 'bg-purple-100',
}

const SCHEDULE_ITEMS = [
  { day: 'Ter', date: '22/07', subject: 'Inglês Intermediário', teacher: 'Sarah Mitchell', time: '19:00', level: 'B1' },
  { day: 'Qui', date: '24/07', subject: 'Conversação Avançada', teacher: 'James Carter', time: '18:00', level: 'B2' },
  { day: 'Sáb', date: '26/07', subject: 'Gramática & Writing', teacher: 'Sarah Mitchell', time: '10:00', level: 'B1' },
]

const DAY_GRADIENTS: Record<string, string> = {
  Ter: 'from-blue-500 to-blue-600',
  Qui: 'from-violet-500 to-purple-600',
  Sáb: 'from-indigo-500 to-blue-600',
}

const AI_RESPONSES: Record<string, { text: string; card?: CardMessage }> = {
  'Minhas aulas': {
    text: 'Encontrei suas próximas aulas. Confira abaixo — você tem **3 aulas agendadas** nesta semana. Posso ajudar a reagendar ou ver detalhes de alguma?',
    card: { type: 'schedule', items: SCHEDULE_ITEMS },
  },
  'Consultar horários': {
    text: 'Encontrei suas próximas aulas. Confira abaixo — você tem **3 aulas agendadas** nesta semana. Posso ajudar a reagendar ou ver detalhes de alguma?',
    card: { type: 'schedule', items: SCHEDULE_ITEMS },
  },
  'Materiais de estudo': {
    text: 'Aqui estão seus **materiais mais recentes** disponíveis:\n\n📄 **Unit 4: Business English** — PDF · 2.4 MB\n🎵 **Listening Practice B1** — Áudio · 12 min\n📖 **Grammar Workbook Ch.7** — PDF · 1.8 MB\n🃏 **Vocabulary Flashcards** — 45 cards\n\nQuer que eu abra algum material agora?',
  },
  'Agendar aula': {
    text: 'Para agendar sua próxima aula, preciso de algumas informações:\n\n1️⃣ **Qual idioma?** (Inglês, Espanhol, Francês…)\n2️⃣ **Com qual professor?** (posso sugerir os disponíveis)\n3️⃣ **Qual horário prefere?**\n\nResponda com suas preferências e confirmo o agendamento! 😊',
  },
  'Contatar professor': {
    text: 'Seus professores estão disponíveis para contato:\n\n👩‍🏫 **Sarah Mitchell** — Inglês\n🟢 Online agora · Responde em ~5 min\n\n👨‍🏫 **James Carter** — Conversação\n🟡 Disponível após 17h hoje\n\nQuer que eu envie uma mensagem para algum deles?',
  },
}

const FALLBACK = 'Entendido! Estou verificando as informações para você... 🔍\n\nAssim que encontrar, te aviso. Há algo mais em que posso ajudar?'

// ─── Helpers ───────────────────────────────────────────────────────────────────

function now() {
  return new Date().toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' })
}

// ─── Rich text (bold via **) ───────────────────────────────────────────────────

function RichText({ text }: { text: string }) {
  return (
    <>
      {text.split('\n').map((line, i, arr) => (
        <span key={i}>
          {line.split(/\*\*(.*?)\*\*/g).map((seg, j) =>
            j % 2 === 1 ? <strong key={j} className="font-semibold">{seg}</strong> : seg
          )}
          {i < arr.length - 1 && <br />}
        </span>
      ))}
    </>
  )
}

// ─── Icons ─────────────────────────────────────────────────────────────────────

function SendIcon() {
  return (
    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
      <path d="M2 8h12M9.5 3.5 14 8l-4.5 4.5" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
    </svg>
  )
}

function AttachIcon() {
  return (
    <svg width="17" height="17" viewBox="0 0 17 17" fill="none">
      <path d="M14.5 8.5 8 15a5 5 0 0 1-7.07-7.07l7.5-7.5a3 3 0 0 1 4.24 4.24L6 11.34a1 1 0 0 1-1.41-1.41L11 4" stroke="currentColor" strokeWidth="1.4" strokeLinecap="round" strokeLinejoin="round"/>
    </svg>
  )
}

function ChevronDownIcon() {
  return (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
      <path d="M3 5l4 4 4-4" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
    </svg>
  )
}

function SparkleIcon() {
  return (
    <svg width="15" height="15" viewBox="0 0 15 15" fill="none">
      <path d="M7.5 1.5v2M7.5 11.5v2M1.5 7.5h2M11.5 7.5h2M3.4 3.4l1.42 1.42M10.17 10.17l1.42 1.42M3.4 11.6l1.42-1.42M10.17 4.83l1.42-1.42" stroke="currentColor" strokeWidth="1.3" strokeLinecap="round"/>
      <circle cx="7.5" cy="7.5" r="2" stroke="currentColor" strokeWidth="1.3"/>
    </svg>
  )
}

// ─── AI Avatar ─────────────────────────────────────────────────────────────────

function AiAvatar({ size = 'sm' }: { size?: 'sm' | 'lg' }) {
  const sz = size === 'lg' ? 'w-14 h-14 text-base' : 'w-9 h-9 text-xs'
  return (
    <div className={`${sz} rounded-2xl bg-gradient-to-br from-blue-500 to-violet-600 flex items-center justify-center text-white font-bold flex-shrink-0 shadow-md shadow-blue-200/60 relative`}>
      <span>AI</span>
      {size === 'lg' && (
        <div className="absolute -bottom-0.5 -right-0.5 w-4 h-4 rounded-full bg-emerald-400 border-2 border-white flex items-center justify-center">
          <span className="text-[7px] text-white font-bold">✓</span>
        </div>
      )}
    </div>
  )
}

// ─── Typing indicator ──────────────────────────────────────────────────────────

function TypingIndicator() {
  return (
    <div className="flex items-end gap-3 px-4 sm:px-6 mb-1">
      <AiAvatar size="sm" />
      <div className="flex flex-col gap-1">
        <div className="bg-white border border-slate-100 rounded-2xl rounded-bl-sm px-4 py-3 shadow-sm inline-flex items-center gap-1.5">
          {[0, 1, 2].map(i => (
            <div
              key={i}
              className="w-2 h-2 rounded-full bg-slate-300 animate-bounce"
              style={{ animationDelay: `${i * 0.18}s`, animationDuration: '0.9s' }}
            />
          ))}
        </div>
        <span className="text-[10px] text-slate-400 ml-1">Traduca AI está digitando...</span>
      </div>
    </div>
  )
}

// ─── Schedule card ─────────────────────────────────────────────────────────────

function ScheduleCard({ items }: { items: CardMessage['items'] }) {
  return (
    <div className="mt-3 space-y-2">
      {items.map(item => (
        <div key={item.date} className="flex items-center gap-3 bg-slate-50 border border-slate-100 rounded-xl px-3 py-2.5 hover:border-blue-100 hover:bg-blue-50/30 transition-all cursor-pointer group">
          <div className={`w-11 h-11 rounded-xl bg-gradient-to-br ${DAY_GRADIENTS[item.day] ?? 'from-blue-500 to-blue-600'} flex flex-col items-center justify-center text-white flex-shrink-0`}>
            <span className="text-[9px] font-bold uppercase tracking-wider opacity-80">{item.day}</span>
            <span className="text-sm font-bold leading-tight">{item.date.split('/')[0]}</span>
          </div>
          <div className="flex-1 min-w-0">
            <p className="text-xs font-semibold text-slate-800 truncate">{item.subject}</p>
            <p className="text-[11px] text-slate-400 truncate">Prof. {item.teacher}</p>
          </div>
          <div className="text-right flex-shrink-0">
            <p className="text-xs font-bold text-slate-700">{item.time}</p>
            <span className="text-[10px] bg-blue-50 text-blue-500 font-medium px-1.5 py-0.5 rounded-full">{item.level}</span>
          </div>
        </div>
      ))}
    </div>
  )
}

// ─── Message bubble ────────────────────────────────────────────────────────────

function MessageBubble({ msg }: { msg: Message }) {
  const isAi = msg.role === 'ai'
  return (
    <div className={`flex items-end gap-3 px-4 sm:px-6 mb-5 ${isAi ? '' : 'flex-row-reverse'}`}>
      {isAi && <AiAvatar size="sm" />}
      <div className={`max-w-[82%] sm:max-w-[68%] flex flex-col gap-1.5 ${isAi ? 'items-start' : 'items-end'}`}>
        <div className={`px-4 py-3 rounded-2xl text-sm leading-relaxed ${
          isAi
            ? 'bg-white border border-slate-100 text-slate-700 rounded-bl-sm shadow-sm'
            : 'bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-br-sm shadow-md shadow-blue-200/50'
        }`}>
          <RichText text={msg.text} />
          {msg.card?.type === 'schedule' && <ScheduleCard items={msg.card.items} />}
        </div>
        <span className="text-[10px] text-slate-400 px-1">{msg.time}</span>
      </div>
    </div>
  )
}

// ─── Quick action chip (compact, for mid-chat) ─────────────────────────────────

function QuickChip({ action, onClick }: { action: typeof QUICK_ACTIONS[0]; onClick: () => void }) {
  return (
    <button
      onClick={onClick}
      className="flex-shrink-0 flex items-center gap-1.5 text-[11px] bg-white border border-slate-200 text-slate-600 font-medium px-3 py-1.5 rounded-full hover:border-blue-200 hover:text-blue-600 hover:bg-blue-50 active:scale-95 transition-all whitespace-nowrap"
    >
      <span>{action.icon}</span>
      {action.label}
    </button>
  )
}

// ─── Initial screen ────────────────────────────────────────────────────────────

function InitialScreen({ onAction }: { onAction: (label: string) => void }) {
  return (
    <div className="flex-1 flex flex-col items-center justify-center px-4 sm:px-8 py-10">
      {/* Hero avatar + greeting */}
      <div className="flex flex-col items-center text-center mb-10">
        <div className="relative mb-5">
          <AiAvatar size="lg" />
          <div className="absolute -inset-2 rounded-3xl bg-gradient-to-br from-blue-100 to-violet-100 -z-10" />
        </div>
        <h2 className="text-2xl font-bold text-slate-800 mb-1">Olá, Felix! 👋</h2>
        <p className="text-slate-500 text-sm max-w-xs leading-relaxed">
          Sou a <span className="font-semibold text-blue-600">Traduca AI</span>, sua assistente virtual. Como posso ajudar com suas aulas hoje?
        </p>
      </div>

      {/* Quick action cards — 2-col grid */}
      <div className="w-full max-w-md">
        <p className="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-3 text-center">O que você precisa?</p>
        <div className="grid grid-cols-2 gap-3">
          {QUICK_ACTIONS.map(action => (
            <button
              key={action.id}
              onClick={() => onAction(action.label)}
              className={`flex items-center gap-3 px-4 py-3.5 rounded-2xl border text-left transition-all active:scale-95 group ${ACTION_COLORS[action.color]}`}
            >
              <div className={`w-9 h-9 rounded-xl ${ACTION_ICON_COLORS[action.color]} flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-110 transition-transform`}>
                {action.icon}
              </div>
              <span className="text-xs font-semibold leading-snug">{action.label}</span>
            </button>
          ))}
          {/* Last card full-width if odd */}
        </div>
      </div>
    </div>
  )
}

// ─── Chat screen messages area ─────────────────────────────────────────────────

function MessagesArea({ messages, isTyping }: { messages: Message[]; isTyping: boolean }) {
  const endRef = useRef<HTMLDivElement>(null)
  useEffect(() => {
    endRef.current?.scrollIntoView({ behavior: 'smooth' })
  }, [messages, isTyping])

  return (
    <div className="flex-1 overflow-y-auto py-5">
      {messages.map(m => <MessageBubble key={m.id} msg={m} />)}
      {isTyping && <TypingIndicator />}
      <div ref={endRef} />
    </div>
  )
}

// ─── Input bar ─────────────────────────────────────────────────────────────────

function InputBar({
  value,
  onChange,
  onSend,
  disabled,
}: {
  value: string
  onChange: (v: string) => void
  onSend: (v: string) => void
  disabled: boolean
}) {
  const handleKey = (e: KeyboardEvent<HTMLInputElement>) => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); onSend(value) }
  }
  return (
    <div className="px-4 sm:px-6 pb-5 pt-3 border-t border-slate-100 bg-white/80 backdrop-blur-sm flex-shrink-0">
      <div className="flex items-center gap-2 bg-white border border-slate-200 rounded-2xl px-3 py-2.5 shadow-sm focus-within:border-blue-400 focus-within:ring-2 focus-within:ring-blue-50 transition-all">
        <button className="text-slate-400 hover:text-blue-500 transition-colors p-1 flex-shrink-0">
          <AttachIcon />
        </button>
        <input
          type="text"
          value={value}
          onChange={e => onChange(e.target.value)}
          onKeyDown={handleKey}
          placeholder="Digite sua mensagem..."
          disabled={disabled}
          className="flex-1 bg-transparent text-sm text-slate-700 placeholder:text-slate-400 outline-none min-w-0 py-0.5 disabled:opacity-60"
        />
        <button
          onClick={() => onSend(value)}
          disabled={!value.trim() || disabled}
          className="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center flex-shrink-0 hover:from-blue-600 hover:to-blue-700 disabled:opacity-40 disabled:cursor-not-allowed active:scale-95 transition-all shadow-sm"
        >
          <SendIcon />
        </button>
      </div>
      <p className="text-center text-[10px] text-slate-300 mt-2.5 tracking-wide">
        Traduca AI · Assistente de IA Educacional
      </p>
    </div>
  )
}

// ─── Chat header ───────────────────────────────────────────────────────────────

function ChatHeader({ view, onReset }: { view: View; onReset: () => void }) {
  return (
    <div className="flex items-center gap-4 px-4 sm:px-6 py-4 border-b border-slate-100 bg-white flex-shrink-0">
      {/* Left: avatar + info */}
      <div className="relative flex-shrink-0">
        <div className="w-11 h-11 rounded-2xl bg-gradient-to-br from-blue-500 to-violet-600 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-blue-200/60">
          AI
        </div>
        <div className="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-emerald-400 border-2 border-white" />
      </div>
      <div className="flex-1 min-w-0">
        <div className="flex items-center gap-2">
          <h2 className="font-bold text-slate-800 text-base leading-none">Traduca AI</h2>
          <span className="text-[10px] bg-violet-50 text-violet-600 font-semibold px-1.5 py-0.5 rounded-full border border-violet-100 tracking-wide flex-shrink-0">PRO</span>
        </div>
        <div className="flex items-center gap-2 mt-1">
          <span className="flex items-center gap-1 text-[11px] text-emerald-500 font-medium">
            <span className="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block" />
            Online
          </span>
          <span className="text-slate-200">·</span>
          <span className="text-[11px] text-slate-400 truncate">Assistente virtual da TraducaIdiomas</span>
        </div>
      </div>
      {/* Right: actions */}
      <div className="flex items-center gap-1 flex-shrink-0">
        {view === 'chat' && (
          <button
            onClick={onReset}
            className="flex items-center gap-1.5 text-[11px] font-medium text-slate-400 hover:text-blue-500 hover:bg-blue-50 px-3 py-1.5 rounded-xl transition-all"
          >
            <SparkleIcon />
            <span className="hidden sm:inline">Nova conversa</span>
          </button>
        )}
        <button className="w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-all">
          <ChevronDownIcon />
        </button>
      </div>
    </div>
  )
}

// ─── Compact quick chips (shown mid-chat below messages) ───────────────────────

function MidChatSuggestions({ onAction, disabled }: { onAction: (label: string) => void; disabled: boolean }) {
  return (
    <div className="px-4 sm:px-6 pb-3 flex-shrink-0">
      <div className="flex gap-2 overflow-x-auto pb-1">
        {QUICK_ACTIONS.map(a => (
          <button
            key={a.id}
            onClick={() => !disabled && onAction(a.label)}
            disabled={disabled}
            className="flex-shrink-0 flex items-center gap-1.5 text-[11px] bg-white border border-slate-200 text-slate-600 font-medium px-3 py-1.5 rounded-full hover:border-blue-200 hover:text-blue-600 hover:bg-blue-50 active:scale-95 transition-all whitespace-nowrap disabled:opacity-50"
          >
            <span>{a.icon}</span>
            {a.label}
          </button>
        ))}
      </div>
    </div>
  )
}

// ─── Root App ──────────────────────────────────────────────────────────────────

export default function App() {
  const [view, setView] = useState<View>('initial')
  const [messages, setMessages] = useState<Message[]>([])
  const [input, setInput] = useState('')
  const [isTyping, setIsTyping] = useState(false)

  const pushAiMessage = (text: string, card?: CardMessage) => {
    const aiMsg: Message = {
      id: Date.now().toString(),
      role: 'ai',
      text,
      time: now(),
      card,
    }
    setMessages(prev => [...prev, aiMsg])
  }

  const handleAction = (label: string) => {
    if (isTyping) return

    // On first action from initial screen, add greeting + user message
    const isFirstInteraction = view === 'initial'
    setView('chat')

    const userMsg: Message = { id: (Date.now() - 1).toString(), role: 'user', text: label, time: now() }

    setMessages(prev => {
      const greeting: Message = {
        id: 'greeting',
        role: 'ai',
        text: 'Olá, Felix! Sou a Traduca AI. Como posso ajudar com suas aulas hoje?',
        time: now(),
      }
      return isFirstInteraction ? [greeting, userMsg] : [...prev, userMsg]
    })

    setIsTyping(true)
    setTimeout(() => {
      setIsTyping(false)
      const response = AI_RESPONSES[label]
      pushAiMessage(
        response?.text ?? FALLBACK,
        response?.card
      )
    }, 1800)
  }

  const handleSend = (text: string) => {
    if (!text.trim() || isTyping) return
    setInput('')

    const isFirst = view === 'initial'
    setView('chat')

    const userMsg: Message = { id: (Date.now() - 1).toString(), role: 'user', text: text.trim(), time: now() }

    setMessages(prev => {
      const greeting: Message = {
        id: 'greeting',
        role: 'ai',
        text: 'Olá, Felix! Sou a Traduca AI. Como posso ajudar com suas aulas hoje?',
        time: now(),
      }
      return isFirst ? [greeting, userMsg] : [...prev, userMsg]
    })

    setIsTyping(true)
    setTimeout(() => {
      setIsTyping(false)
      const match = Object.keys(AI_RESPONSES).find(k => text.toLowerCase().includes(k.toLowerCase()))
      const response = match ? AI_RESPONSES[match] : null
      pushAiMessage(response?.text ?? FALLBACK, response?.card)
    }, 1800)
  }

  const handleReset = () => {
    setView('initial')
    setMessages([])
    setInput('')
    setIsTyping(false)
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-violet-50/20 flex items-center justify-center p-4 sm:p-6">
      {/* Chat window card */}
      <div className="w-full max-w-2xl bg-white rounded-3xl shadow-2xl shadow-slate-200/60 border border-slate-100 flex flex-col overflow-hidden" style={{ minHeight: '600px', maxHeight: '88vh' }}>

        {/* Header */}
        <ChatHeader view={view} onReset={handleReset} />

        {/* Body */}
        {view === 'initial' ? (
          <InitialScreen onAction={handleAction} />
        ) : (
          <MessagesArea messages={messages} isTyping={isTyping} />
        )}

        {/* Quick suggestions strip — only in chat view */}
        {view === 'chat' && (
          <MidChatSuggestions onAction={handleAction} disabled={isTyping} />
        )}

        {/* Input */}
        <InputBar
          value={input}
          onChange={setInput}
          onSend={handleSend}
          disabled={isTyping}
        />
      </div>
    </div>
  )
}
