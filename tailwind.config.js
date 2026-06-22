/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  darkMode: "class",
  theme: {
    extend: {
      "colors": {
        "primary": "#006b5f",
        "on-primary": "#ffffff",
        "primary-container": "#79f7e3",
        "on-primary-container": "#00201c",
        "secondary": "#4a635f",
        "on-secondary": "#ffffff",
        "secondary-container": "#cce8e2",
        "on-secondary-container": "#05201c",
        "tertiary": "#456179",
        "on-tertiary": "#ffffff",
        "tertiary-container": "#cce5ff",
        "on-tertiary-container": "#001e31",
        "error": "#ba1a1a",
        "on-error": "#ffffff",
        "error-container": "#ffdad6",
        "on-error-container": "#410002",
        "background": "#fbfdfa",
        "on-background": "#191c1b",
        "surface": "#fbfdfa",
        "on-surface": "#191c1b",
        "surface-variant": "#dae5e1",
        "on-surface-variant": "#3f4947",
        "outline": "#6f7977",
        "outline-variant": "#bec9c6",
        "inverse-surface": "#2e3130",
        "inverse-on-surface": "#eff1ef",
        "inverse-primary": "#59dbc7",
        "surface-dim": "#dbdcdb",
        "surface-bright": "#fbfdfa",
        "surface-container-lowest": "#ffffff",
        "surface-container-low": "#f5f7f5",
        "surface-container": "#efebef",
        "surface-container-high": "#e9ebe9",
        "surface-container-highest": "#e3e5e3"
      },
      "borderRadius": {
        "DEFAULT": "0.25rem",
        "lg": "0.5rem",
        "xl": "0.75rem",
        "full": "9999px"
      },
      "spacing": {
        "margin-mobile": "16px",
        "container-max": "1440px",
        "margin-desktop": "64px",
        "unit": "4px",
        "gutter": "24px"
      },
      "fontFamily": {
        "display-lg": ["Hanken Grotesk", "sans-serif"],
        "body-lg": ["Hanken Grotesk", "sans-serif"],
        "headline-lg-mobile": ["Hanken Grotesk", "sans-serif"],
        "label-bold": ["Hanken Grotesk", "sans-serif"],
        "body-md": ["Hanken Grotesk", "sans-serif"],
        "headline-md": ["Hanken Grotesk", "sans-serif"],
        "headline-lg": ["Hanken Grotesk", "sans-serif"],
        "label-sm": ["Hanken Grotesk", "sans-serif"]
      },
      "fontSize": {
        "display-lg": ["72px", { lineHeight: "1.1", letterSpacing: "-0.04em", fontWeight: "700" }],
        "body-lg": ["18px", { lineHeight: "1.6", fontWeight: "400" }],
        "headline-lg-mobile": ["32px", { lineHeight: "1.2", fontWeight: "700" }],
        "label-bold": ["14px", { lineHeight: "1.0", letterSpacing: "0.1em", fontWeight: "700" }],
        "body-md": ["16px", { lineHeight: "1.5", fontWeight: "400" }],
        "headline-md": ["24px", { lineHeight: "1.3", fontWeight: "600" }],
        "headline-lg": ["40px", { lineHeight: "1.2", letterSpacing: "-0.02em", fontWeight: "700" }],
        "label-sm": ["12px", { lineHeight: "1.0", fontWeight: "500" }]
      }
    },
  },
  plugins: [],
}
